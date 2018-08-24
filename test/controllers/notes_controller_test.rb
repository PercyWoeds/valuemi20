require 'test_helper'

class NotesControllerTest < ActionController::TestCase
  setup do
    @note = notes(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:notes)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create note" do
    assert_difference('Note.count') do
      post :create, note: { caja: @note.caja, cantidad: @note.cantidad, cod_cli: @note.cod_cli, cod_emp: @note.cod_emp, cod_prod: @note.cod_prod, fecha: @note.fecha, fpago: @note.fpago, igv: @note.igv, importe: @note.importe, numero: @note.numero, odometro: @note.odometro, placa: @note.placa, precio: @note.precio, ruc: @note.ruc, serie: @note.serie, td: @note.td, turno: @note.turno }
    end

    assert_redirected_to note_path(assigns(:note))
  end

  test "should show note" do
    get :show, id: @note
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @note
    assert_response :success
  end

  test "should update note" do
    patch :update, id: @note, note: { caja: @note.caja, cantidad: @note.cantidad, cod_cli: @note.cod_cli, cod_emp: @note.cod_emp, cod_prod: @note.cod_prod, fecha: @note.fecha, fpago: @note.fpago, igv: @note.igv, importe: @note.importe, numero: @note.numero, odometro: @note.odometro, placa: @note.placa, precio: @note.precio, ruc: @note.ruc, serie: @note.serie, td: @note.td, turno: @note.turno }
    assert_redirected_to note_path(assigns(:note))
  end

  test "should destroy note" do
    assert_difference('Note.count', -1) do
      delete :destroy, id: @note
    end

    assert_redirected_to notes_path
  end
end
