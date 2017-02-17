require 'test_helper'

class CreditsControllerTest < ActionController::TestCase
  setup do
    @credit = credits(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:credits)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create credit" do
    assert_difference('Credit.count') do
      post :create, credit: { code: @credit.code, fecha: @credit.fecha, mod_factura: @credit.mod_factura, mod_tipo: @credit.mod_tipo, moneda_id: @credit.moneda_id, motivo: @credit.motivo, nota_id: @credit.nota_id, subtotal: @credit.subtotal, tax: @credit.tax, total: @credit.total }
    end

    assert_redirected_to credit_path(assigns(:credit))
  end

  test "should show credit" do
    get :show, id: @credit
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @credit
    assert_response :success
  end

  test "should update credit" do
    patch :update, id: @credit, credit: { code: @credit.code, fecha: @credit.fecha, mod_factura: @credit.mod_factura, mod_tipo: @credit.mod_tipo, moneda_id: @credit.moneda_id, motivo: @credit.motivo, nota_id: @credit.nota_id, subtotal: @credit.subtotal, tax: @credit.tax, total: @credit.total }
    assert_redirected_to credit_path(assigns(:credit))
  end

  test "should destroy credit" do
    assert_difference('Credit.count', -1) do
      delete :destroy, id: @credit
    end

    assert_redirected_to credits_path
  end
end
