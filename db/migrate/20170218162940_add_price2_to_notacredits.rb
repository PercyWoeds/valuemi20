class AddPrice2ToNotacredits < ActiveRecord::Migration
  def change
    add_column :notacredits, :price2, :float
  end
end
